# Welcome to the Themefic Plugin Test!
Thank you for taking the time to participate in this skill test. We are excited to see your abilities in action and to get a glimpse of your expertise in WordPress Plugin development. **Your task is to customize the provided plugin and create the functions based on the requirements provided.**

## Getting started
1. Clone the `master` branch of this `plugindevtest` repository: `git clone https://github.com/Themefic-Team/plugindevtest.git`
2. Read the [Project requirements](#project-requirement) to begin development.
3. Once you're done, please commit and push your work to a branch named in the following format: `yourname-birthday`. Additionally, make sure to include a live demo link of your project in the `readme.md` file,.
  
## Project Requirement
- Use the "TF Service Booking" plugin we provided on this repo for the development.
- Once you install the plugin, you will see a "TF Services" post type on your Dashboard.
 - Implement AJAX pagination on the service archive page to load more results without refreshing.
  - Develop a shortcode named `tf_service_result` that displays all services.
  - Create a custom Gutenberg block or Elementor Addon or Bricks Builder Addon that displays all services.
  - On plugin activation, a page titled `TF Service Result` will be automatically created with a specific page template. Inside this page include `tf_service_result` shortcode into the editor.
  - Each services can be added to the cart and users can check out using the WooCommerce payment process.
- Create an <strong>Ajax based Search form</strong> for the frontend of the site.
    - User can search for only "TF Services" on this search form.
    - After typing a keyword, the search result will show 5 relevant services just under the search form.
    - If there are more than 5 results, include a "View More" button.
    - Clicking the "View More" button should lead to a page displaying all relevant services (based on the search query).
 
 

## Other Requirements
- You can only install the WooCommerce plugin.
- Do not install any additional plugins.
- For the frontend, Use the theme “[Storefront](https://wordpress.org/themes/storefront)” only.
- Using OOP and SCSS is preferred.
- Test your plugin using the [Plugin Check plugin](https://wordpress.org/plugins/plugin-check/) before submitting, and ensure that it has no errors or critical warnings.

### PHP & JS
- Ensure your PHP & JS are free of errors, formatted consistently, and documented appropriately.

### HTML
- Ensure your HTML is valid, semantic and accessible (e.g. using `aria` attributes where appropriate).

### CSS
- Write your CSS using SCSS.
- Follow [BEM](https://getbem.com/) naming conventions.
- Demonstrate usage of:
    - modern CSS techniques such as Flexbox, Grid, `clamp`, etc.;
    - SCSS variables and at least one function or mixin;
    - responsive design techniques.

### Git
- Ensure your git commit(s) are logical and accompanied by clear, concise messages.
  
## Dos and don’ts
### Please do:
- **Do** follow our project requirement 100%.
- **Do** ask questions! If the requirements of the test are unclear or you have any issues completing or submitting it, please email [career@themefic.com](mailto:career@themefic.com).
### Please don’t:
- **Don’t** install or use any additional frameworks, libraries, page builders or plugins that aren’t instructed in the project requirement, including Bootstrap, Tailwind, etc.
- **Don’t** pull from or push to any branches of the `plugindevtest` repository *except* for the branch you have created for you.

## Creators
[Themefic](https://themefic.com) develop WordPress Themes & Plugins for clients Worldwide.
