#!/usr/bin/env bash
#
# Copy third-party (vendored) assets into the distributable assets/ directory.
# FontAwesome 5 ships pre-built CSS + webfonts; we only relocate them so the
# library can enqueue them without requiring consumers to run a build.
#
set -e

fa_dir="node_modules/@fortawesome/fontawesome-free"

if [ ! -d "$fa_dir" ]; then
	echo "Error: ${fa_dir} not found. Run 'npm install' first." >&2
	exit 1
fi

mkdir -p assets/css assets/webfonts

# FontAwesome 5 stylesheet (handle: fa5-all). It references ../webfonts/*, so the
# webfonts must sit alongside assets/css as assets/webfonts.
cp "${fa_dir}/css/all.min.css" assets/css/all.min.css
cp -R "${fa_dir}/webfonts/." assets/webfonts/

echo "Copied FontAwesome 5 CSS and webfonts into assets/."
