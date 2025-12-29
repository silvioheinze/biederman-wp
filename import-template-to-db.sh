#!/bin/bash
# Script to import block template content into WordPress database

cd "$(dirname "$0")"

# Read template file
TEMPLATE_FILE="biederman-wp/templates/front-page.html"
FRONT_PAGE_ID=2  # Update this if your front page has a different ID

if [ ! -f "$TEMPLATE_FILE" ]; then
    echo "Error: Template file not found: $TEMPLATE_FILE"
    exit 1
fi

# Escape single quotes and newlines for MySQL
CONTENT=$(cat "$TEMPLATE_FILE" | sed "s/'/''/g" | sed ':a;N;$!ba;s/\n/\\n/g')

# Update database
docker-compose exec -T db mysql -u wordpress -pwordpress wordpress <<EOF
UPDATE wp_posts 
SET post_content = '$CONTENT' 
WHERE ID = $FRONT_PAGE_ID;
EOF

if [ $? -eq 0 ]; then
    echo "✓ Successfully imported template content into page ID: $FRONT_PAGE_ID"
    echo "✓ You can now edit the page in the WordPress editor at: http://localhost:8080/wp-admin/post.php?post=$FRONT_PAGE_ID&action=edit"
else
    echo "✗ Error importing template"
    exit 1
fi

