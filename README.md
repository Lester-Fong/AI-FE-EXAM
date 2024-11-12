# Publish-Article

A role-based content management system built to facilitate writing and editing articles within a company structure. The project includes two roles: **Writer** and **Editor**. Writers are responsible for drafting articles, while Editors can review, edit, and publish or hide them. Editors also manage users and company details.

## Features

-   **Role-based Application**: Roles include Writer and Editor, with access control for creating, editing, and publishing articles.
-   **Article Management**: Writers create and edit articles; Editors finalize and publish articles.
-   **User and Company Management**: Editors can create and manage user profiles and company information.
-   **Authentication & Authorization**: Secure login and role-based access control.
-   **Published Articles Display**: Publicly display all published articles.

## Usage

-   **Writer Role**: Write and submit articles for review within a specific company context.
-   **Editor Role**: Review, edit, and decide on publishing or hiding articles, along with managing user and company details.

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

Make sure you have the following installed:

-   Node.js and npm
-   PHP and Composer
-   MySQL or your preferred database

### Installation

1. **Clone the Project**

    ```bash
    git clone https://github.com/Lester-Fong/AI-FE-EXAM.git
    ```

2. **Install packages**

    ```bash
     npm run watch
     composer install
    ```

3. **Create a database name publish-article**

4. **migrate the tables and execute the seeders**

    ```bash
    php artisan migrate --seed
    ```

5. **Generate Authentication key**

    ```bash
    php artisan passport:client --password
    ```

    **Note:** Copy the secret key generate by the command and paste it to HelperToken.php $user_client_secret variable.

6. **Set up Apache virtualhost on Windows** <br/>
   **~ Kindly follow the guide in this link ~**
   <a href="https://stackoverflow.com/a/2658510/19356014" target="_blank">Virtual Host Setup</a>

8. **Run the Project Locally**
    ```bash
    npm run watch
    ```

<br/><br/>

### These are the **Credentials** that is generated after you seed the database:

**For writer:** <br/>
**email**: `writer@user.com`, <br/>
**password**: `Article_writer_123` <br/>
**For editor:** <br/>
**email**: `editor@user.com`, <br/>
**password**: `Article_editor_123` <br/>

<br/><br/>

## Note: If you have any questions regarding setting up or running the project, you can reach out to me and I will be happy to guide you. Thanks!

```bash
   lesternielcfong22@gmail.com
```

<br/><br/>

### Acknowledgments

### This project was made possible with the following tools and technologies:

**Vue3** - Frontend JavaScript framework <br/>
**Vuex** - State management library for Vue.js <br/>
**Rebing** **GraphQL** - GraphQL integration with Laravel <br/>
**Laravel** **8** - Backend PHP framework <br/>
**Bootstrap** - Responsive CSS framework <br/>
**VueSweetAlert2** - SweetAlert2 integration for Vue <br/>
**VueDatePicker** - Custom date picker component for Vue <br/>
**DataTable** - JavaScript library for dynamic tables <br/>
**Summernote** - WYSIWYG editor for article content creation <br/>
