#!/usr/bin/env python3
"""
Script to import block template content into WordPress database
"""
import subprocess
import sys
import os

# Configuration
template_file = "biederman-wp/templates/front-page.html"
front_page_id = 2  # Update this if your front page has a different ID

# Change to script directory
os.chdir(os.path.dirname(os.path.abspath(__file__)))

try:
    # Read template file
    with open(template_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Escape single quotes and backslashes for MySQL
    content_escaped = content.replace('\\', '\\\\').replace("'", "''")
    
    # Build MySQL command
    mysql_cmd = f"UPDATE wp_posts SET post_content = '{content_escaped}' WHERE ID = {front_page_id};"
    
    # Execute via docker-compose
    result = subprocess.run(
        ['docker-compose', 'exec', '-T', 'db', 'mysql', '-u', 'wordpress', '-pwordpress', 'wordpress'],
        input=mysql_cmd,
        cwd=os.getcwd(),
        capture_output=True,
        text=True
    )
    
    if result.returncode == 0:
        print(f"✓ Successfully imported template content into page ID: {front_page_id}")
        print(f"✓ You can now edit the page in the WordPress editor at:")
        print(f"  http://localhost:8080/wp-admin/post.php?post={front_page_id}&action=edit")
    else:
        print(f"✗ Error importing template:")
        print(f"  {result.stderr}")
        sys.exit(1)
        
except FileNotFoundError as e:
    print(f"✗ Error: File not found: {e}")
    sys.exit(1)
except Exception as e:
    print(f"✗ Error: {e}")
    sys.exit(1)

