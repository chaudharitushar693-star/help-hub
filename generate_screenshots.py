#!/usr/bin/env python3
"""
Screenshot Generator for YATRI SAHAYATA using Headless Google Chrome
Developer: Nidhi Chaudhary (0108CS241082), SATI Vidisha
"""

import os
import subprocess
import time
import sqlite3

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
SCREENSHOTS_DIR = os.path.join(BASE_DIR, "screenshots")
HTML_DUMP_DIR = os.path.join(BASE_DIR, "screenshots", "rendered_html")
CHROME_BIN = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"

os.makedirs(SCREENSHOTS_DIR, exist_ok=True)
os.makedirs(HTML_DUMP_DIR, exist_ok=True)

# Import renderer methods from server
import server

class DummyHandler:
    pass

dummy = DummyHandler()

def generate_pages_and_capture():
    print("Generating HTML snapshots and capturing Chrome screenshots...")

    admin_user = {
        "id": 1,
        "name": "Nidhi Chaudhary (Admin)",
        "email": "admin@yatrisahayata.org",
        "phone": "+91 98765 43210",
        "role_id": 1,
        "role_name": "admin"
    }

    pilgrim_user = {
        "id": 2,
        "name": "Ramesh Kumar Sharma",
        "email": "ramesh.sharma@example.com",
        "phone": "+91 98260 12345",
        "role_id": 2,
        "role_name": "user"
    }

    pages = {}

    # 1. Homepage
    pages["screenshot_01_homepage.html"] = render_to_string(lambda h: server.YatriRequestHandler.render_home_page(h, pilgrim_user))

    # 2. Routes & Map
    pages["screenshot_02_routes_map.html"] = render_to_string(lambda h: server.YatriRequestHandler.render_routes_page(h, pilgrim_user, "Vaishno Devi"))

    # 3. Smart Planner
    pages["screenshot_03_smart_planner.html"] = render_to_string(lambda h: server.YatriRequestHandler.render_planner_page(h, pilgrim_user))

    # 4. Medical Emergency Directory
    pages["screenshot_04_medical_directory.html"] = render_to_string(lambda h: server.YatriRequestHandler.render_medical_page(h, pilgrim_user, "Vaishno Devi"))

    # 5. Pilgrim Dashboard
    pages["screenshot_05_pilgrim_dashboard.html"] = render_to_string(lambda h: server.YatriRequestHandler.render_user_dashboard(h, pilgrim_user))

    # 6. Admin Control Center
    pages["screenshot_06_admin_dashboard.html"] = render_to_string(lambda h: server.YatriRequestHandler.render_admin_index(h, admin_user))

    # Write HTML files
    for filename, html in pages.items():
        # Adjust stylesheet and script relative paths for local file:/// rendering
        html_fixed = html.replace('href="/css/yatri-main.css"', f'href="{BASE_DIR}/public/css/yatri-main.css"')
        html_fixed = html_fixed.replace('src="/js/yatri-app.js"', f'src="{BASE_DIR}/public/js/yatri-app.js"')
        html_fixed = html_fixed.replace('src="/js/yatri-map.js"', f'src="{BASE_DIR}/public/js/yatri-map.js"')
        html_fixed = html_fixed.replace('src="/js/yatri-ai.js"', f'src="{BASE_DIR}/public/js/yatri-ai.js"')

        filepath = os.path.join(HTML_DUMP_DIR, filename)
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(html_fixed)

        # Capture with Chrome
        png_name = filename.replace(".html", ".png")
        png_path = os.path.join(SCREENSHOTS_DIR, png_name)

        cmd = [
            CHROME_BIN,
            "--headless",
            "--disable-gpu",
            "--no-sandbox",
            "--user-data-dir=/tmp/chrome_prof",
            "--window-size=1280,950",
            f"--screenshot={png_path}",
            f"file://{filepath}"
        ]

        res = subprocess.run(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        if os.path.exists(png_path):
            print(f"✅ Captured {png_name} ({os.path.getsize(png_path) // 1024} KB)")
        else:
            print(f"❌ Failed to capture {png_name}: {res.stderr.decode()[:200]}")

def render_to_string(render_fn):
    class CapturingHandler(server.YatriRequestHandler):
        def __init__(self):
            self.output = ""
        def send_html(self, html_str, code=200):
            self.output = html_str

    h = CapturingHandler()
    render_fn(h)
    return h.output

if __name__ == "__main__":
    generate_pages_and_capture()
