=== 3D Viewer Block – Bring Your Products to Life with Interactive 3D ===
Contributors: bplugins, abuhayat, shehabulislam
Donate link: https://www.buymeacoffee.com/abuhayat
Tags: block, 3d, 3d model, 3d viewer, Gutenberg block
Requires at least: 6.5
Tested up to: 7.1
Stable tag: 1.2.0
Requires PHP: 7.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Embed 3D models. Display interactive 3D models within a few clicks using the Gutenberg Editor.

== Description ==

Simple Yet Powerful 3D Viewer. Make your website look more interactive and visually appealing with 3D Viewer. This is a block plugin that will add a new block in the block (3D Viewer ) editor of WordPress. You can embed .glb and .gltf model files in posts, pages, and widget areas. It comes with everything you need to make the most out of 3D. advertise your products interestingly.

3D Viewer Block is a powerful and easy-to-use block plugin that allows you to display interactive 3D models in your WordPress posts and pages. With this plugin, you can embed 3D models in your content using the new Gutenberg editor, giving your users an engaging and immersive viewing experience.

With 3D Viewer Block, you can upload 3D models in GLB or GLTF format and customize their appearance with a range of settings, such as exposure, shadow, background color, and camera controls. Visitors can orbit and zoom the model, and optional buttons add fullscreen, zoom, screenshot and download.

The plugin includes an intuitive block interface that simplifies the process of adding 3D models to your content. Simply select the 3D Viewer Block from the Gutenberg block library, upload your model, customize the settings to your liking, and you're done!

3D Viewer Block is optimized for mobile devices and supports touch controls, making it easy for your users to interact with your 3D content on their smartphones and tablets. The plugin is also compatible with WooCommerce, allowing you to showcase 3D models of your products in a stunning and engaging way.

Overall, 3D Viewer Block is a versatile and powerful block plugin that can help you showcase 3D content in your WordPress posts and pages with ease. Try it out today and take your content to the next level!

= For whom = 
* Creative Designer and Agencies. 
* Trainer / Course Creators
* E-commerce sites that want to display their products in 3D.
* Web designer/developer
* Real estate


**[Demos](https://bblockswp.com/demo/3d-viewer/)**


= Features =
- **Controls**: Controls via mouse/touch
- **Responsive**: Works great with any device. 
- **External Source**: Use models from other cloud storage. 


= How to use =
- First, install the 3D Viewer Block plugin
- Add the 3D Viewer block from the "Media" block category in the Gutenberg editor.
- You can change block settings from the right-side settings sidebar.
- Enjoy!

* For installation help click on Installation Tab


= Feedback =
- Did you like this plugin? Dislike it? Do you have a feature request? [Please share your feedback with us](mailto:support@bplugins.com 'Send feedback')


### Check out the Parent Plugin of this plugin-

[**B Blocks**](https://bblockswp.com) – A blocks collection and page building tool for Gutenberg.


### Check out our other WordPress Plugins-

[**Html5 Video Player**](https://bplugins.com/products/html5-video-player/) – Display videos as single and playlist in multiple skins.

[**PDF Poster**](https://bplugins.com/products/pdf-poster/) – Display/Embed PDF files with different styles.

[**Html5 Audio Player**](https://bplugins.com/products/html5-audio-player/) – Listen audios with awesome visuals.

[**StreamCast**](https://bplugins.com/products/streamcast-radio-player/) – Customizable radio player with different skins.

[**3D Viewer**](https://bplugins.com/products/3d-viewer/) – Embed 3D models and 3D products with interaction.

[**Advanced Post Block**](https://bplugins.com/products/advanced-post-block/) – Show posts and custom posts in different layouts.


== Installation ==

= From Gutenberg Editor: =
1. Go to the WordPress Block/Gutenberg Editor
2. Search For **3D Viewer**
3. Click on the **3D Viewer** to add the block

= Download & Upload: =
1. Download the **3D Viewer Block** plugin (*.zip file*)
2. In your admin area, go to the Plugins menu and click on **Add New**
3. Click on **Upload Plugin** and choose the **`3d-viewer-block.zip`** file and click on **Install Now**
4. Activate the plugin and Enjoy!

= Manually: =
1. Download and upload the **3D Viewer Block** plugin to the **`/wp-content/plugins/`** directory
2. Activate the plugin through the Plugins menu in WordPress


== Frequently Asked Questions ==

= Is 3D Viewer Block free? =

Yes, 3D Viewer Block is a free Gutenberg block plugin.

= Does it work with any WordPress theme? =

Yes, it will work with any standard WordPress theme.

= Can I change block settings? =

Yes, you can change block settings from the Gutenberg block editor's right sidebar.

= How many times can I reuse a block? =

You can use unlimited times as you want.

= Where can I get support? =

You can post your questions on the [support forum here](https://wordpress.org/support/plugin/3d-viewer-block/)


== Screenshots ==

1. 3D Viewer
2. Settings


== Changelog ==

= 1.2.0 - 08 Nov 2026 =
* Update: model-viewer library updated to 4.2.2, the same build used by the 3D Viewer plugin.
* Fix: No conflict when the 3D Viewer or 3D Viewer Premium plugin is active alongside this block. One copy of the viewer library loads and the block is registered once.
* Fix: Front-end stylesheet is now built and loaded with the block.
* Fix: A width of 0 now renders the viewer at full width instead of collapsing it to zero width.
* Fix: Zoom in/out buttons work when mouse/touch controls or scroll zoom are turned off.
* Security: Block attribute values are validated before they reach the front-end markup, and a block with malformed attributes renders with defaults instead of raising a PHP error.
* Fix: Background and progress bar colors can be cleared again with a Clear link.
* Fix: Switching device in the Size panel no longer carries the previous device's unit into a new value.
* Fix: A block without a model renders nothing on the live page instead of an empty box.
* Fix: The download button is disabled after a model fails to load.
* Fix: Fullscreen works on iPhone Safari through a full-window overlay, since iOS has no Fullscreen API for page elements.
* Fix: The editor preview now shows the model on WordPress 6.5, which does not print script modules in the admin.
* Fix: Text domain is now `3d-viewer-block`, matching the plugin slug, so wordpress.org translations load.
* Docs: Readme no longer claims OBJ/STL support and names the correct "Media" block category.
* Improvement: New empty state in the editor with a link field and upload button, so a model can be added without opening the sidebar.
* New: Poster image shown while the model loads.
* New: Auto-rotate toggle, exposure and shadow intensity controls.
* New: Background color, and separate width/height for tablet and mobile.
* New: Optional fullscreen, zoom in/out, screenshot and download buttons on the viewer, with a choice of corner.
* New: Progress bar toggle and color, optional loading percentage, and a message when a model fails to load.
* Improvement: Sidebar settings reorganized into Settings and Style tabs.
* Fix: Linked models now have a screen reader name, taken from the file name (for example "oak chair" for oak-chair.glb).
* Accessibility: Viewer buttons are labelled as a group and show a visible keyboard focus ring, the fullscreen button reports its state, loading progress and load failures are announced, and auto-rotate respects the system "reduce motion" setting.


= 1.1.0 - 03 May 2026 =
* Refactor: Migrate to modern WordPress 6.5 Script Modules API.
* Performance: Significant improvement in page load speed by removing React from the frontend.
* Architecture: Modularize codebase and implement namespaces.
* Security: Strengthen file upload validation.
* Optimization: Improve inline style handling and block properties.

= 1.0.8 =
* Fix issue

= 1.0.7 =
* Update Notice

= 1.0.6 =
* Class existing issue fixed.

= 1.0.5 =
* Change the file uploading system

= 1.0.4 =
* Add translate feature

= 1.0.3 =
* Reduce PHP Code.
* Performance Improvement

= 1.0.2 =
* Reduce PHP Code.

= 1.0.1 =
* Performance upgrade

= 1.0.0 =
* Initial Release.