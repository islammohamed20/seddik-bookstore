#!/bin/bash
# =============================================================
#  deploy.sh — سكربت النشر لمشروع seddik-bookstore
#  الاستخدام: sudo bash /var/www/seddik-bookstore/deploy.sh
# =============================================================
set -euo pipefail

# ─── إعدادات ────────────────────────────────────────────────
SITE_DIR="/var/www/seddik-bookstore"
BRANCH="main"
DB_USER="seddik_user"
DB_PASS="CHANGE_ME"
DB_NAME="seddik_bookstore"
BACKUP_DIR="$SITE_DIR/deploy-backups"
TIMESTAMP=$(date +%F-%H%M%S)
PHP="php8.2"
# ─────────────────────────────────────────────────────────────

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
step()  { echo -e "\n${GREEN}[✔] $1${NC}"; }
warn()  { echo -e "${YELLOW}[!] $1${NC}"; }
abort() { echo -e "${RED}[✘] $1${NC}"; php artisan up 2>/dev/null || true; exit 1; }

# ─── 0) ادخل المجلد ─────────────────────────────────────────
cd "$SITE_DIR" || abort "المجلد $SITE_DIR غير موجود"

# ─── 1) تأكد أن Git مهيّأ ───────────────────────────────────
step "فحص Git"
if ! git rev-parse --is-inside-work-tree &>/dev/null; then
    warn "المجلد ليس Git repo — سيتم تهيئته من GitHub"
    git init
    git remote add origin https://github.com/islammohamed20/seddik-bookstore.git
    git fetch --all
    git checkout -B "$BRANCH" "origin/$BRANCH"
fi

# ─── 2) وضع الصيانة ─────────────────────────────────────────
step "تفعيل وضع الصيانة"
$PHP artisan down --refresh=15 2>/dev/null || warn "لم يتمكن من تفعيل maintenance mode"

# ─── 3) نسخ احتياطي ─────────────────────────────────────────
step "نسخ احتياطي من .env وقاعدة البيانات"
mkdir -p "$BACKUP_DIR"
cp .env "$BACKUP_DIR/.env.$TIMESTAMP" 2>/dev/null || warn "لم يوجد .env للنسخ"
mysqldump -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" \
  > "$BACKUP_DIR/db.$TIMESTAMP.sql" 2>/dev/null \
  && echo "  → حُفظت النسخة في $BACKUP_DIR/db.$TIMESTAMP.sql" \
  || warn "فشل mysqldump — تأكد من بيانات DB"

# احتفظ بآخر 5 نسخ فقط (توفير مساحة)
ls -t "$BACKUP_DIR"/db.*.sql 2>/dev/null | tail -n +6 | xargs rm -f 2>/dev/null || true

# ─── 4) سحب آخر كود من GitHub ───────────────────────────────
step "سحب التحديثات من GitHub ($BRANCH)"
git fetch --all
git checkout "$BRANCH"
git reset --hard "origin/$BRANCH"

# ─── 5) استعادة .env إذا محيت ───────────────────────────────
if [ ! -f ".env" ] && [ -f "$BACKUP_DIR/.env.$TIMESTAMP" ]; then
    warn "ملف .env محذوف — سيتم استعادته"
    cp "$BACKUP_DIR/.env.$TIMESTAMP" .env
fi

# ─── 6) اعتماديات PHP ───────────────────────────────────────
step "تثبيت اعتماديات Composer"
composer install --no-dev --optimize-autoloader --no-interaction

# ─── 7) بناء ملفات الواجهة ──────────────────────────────────
step "بناء ملفات Vite (npm)"
if [ -f "package.json" ]; then
    if [ -f "package-lock.json" ]; then
        npm ci 2>/dev/null || npm install
    else
        npm install
    fi
    npm run build
else
    warn "لا يوجد package.json — تم تخطي بناء الواجهة"
fi

# ─── 8) تحديث قاعدة البيانات ────────────────────────────────
step "تنفيذ المايجريشنات"
$PHP artisan migrate --force

# ─── 9) رابط التخزين ────────────────────────────────────────
step "التحقق من رابط storage"
if [ ! -L "public/storage" ]; then
    $PHP artisan storage:link
else
    echo "  → رابط storage موجود بالفعل"
fi

# ─── 10) مسح الكاش وإعادة البناء ───────────────────────────
step "مسح الكاش وإعادة بناء الإعدادات"
$PHP artisan config:clear
$PHP artisan cache:clear
$PHP artisan route:clear
$PHP artisan view:clear
$PHP artisan optimize

# ─── 11) الصلاحيات ──────────────────────────────────────────
step "ضبط الصلاحيات"
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chown -R www-data:www-data "$SITE_DIR"
chmod -R 775 "$SITE_DIR/storage" "$SITE_DIR/bootstrap/cache"

# ─── 12) إعادة تحميل الخدمات ────────────────────────────────
step "إعادة تحميل Apache و PHP-FPM"
systemctl reload apache2 2>/dev/null || warn "تعذّر reload apache2"
systemctl reload php8.2-fpm 2>/dev/null || warn "php-fpm reload تخطّي"

# ─── 13) إعادة تشغيل Queue ──────────────────────────────────
step "إعادة تشغيل Queue Workers"
$PHP artisan queue:restart 2>/dev/null || warn "queue:restart تخطّي"

# ─── 14) رفع وضع الصيانة ────────────────────────────────────
step "رفع وضع الصيانة"
$PHP artisan up

# ─── ملخص النتيجة ───────────────────────────────────────────
echo ""
echo -e "${GREEN}================================================${NC}"
echo -e "${GREEN}  النشر اكتمل بنجاح  ✔${NC}"
echo -e "${GREEN}================================================${NC}"
$PHP artisan about --only=environment 2>/dev/null || true
echo ""
echo "  الموقع: https://elsedeek-store.com"
echo "  النسخة الاحتياطية: $BACKUP_DIR/db.$TIMESTAMP.sql"
echo ""
