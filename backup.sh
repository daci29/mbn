#!/usr/bin/env bash

set -Eeuo pipefail

export TZ=Asia/Tehran

DATE="$(date '+%Y-%m-%d_%H-%M-%S')"
FILE="/tmp/mirzabot_${DATE}.sql"
ARCHIVE="/tmp/mirzabot_${DATE}.sql.gz"

echo "Starting backup..."

mysqldump \
  --single-transaction \
  --routines \
  --triggers \
  --skip-lock-tables \
  --no-tablespaces \
  -h "${MYSQLHOST}" \
  -P "${MYSQLPORT}" \
  -u "${MYSQLUSER}" \
  -p"${MYSQLPASSWORD}" \
  "${MYSQLDATABASE}" > "${FILE}"

gzip -f "${FILE}"

if [ ! -s "${ARCHIVE}" ]; then
    echo "Backup file is empty."
    exit 1
fi

IFS=',' read -ra CHAT_IDS <<< "${BACKUP_CHAT_IDS}"

for CHAT_ID in "${CHAT_IDS[@]}"; do
    CHAT_ID="$(echo "$CHAT_ID" | xargs)"

    curl --fail --silent --show-error \
      -F "chat_id=${CHAT_ID}" \
      -F "document=@${ARCHIVE}" \
      -F "caption=MirzaBot backup - ${DATE}" \
      "https://api.telegram.org/bot${BACKUP_BOT_TOKEN}/sendDocument"
done

rm -f "${ARCHIVE}"

echo "Backup sent successfully."
