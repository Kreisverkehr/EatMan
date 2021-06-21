<!--
*** Thanks for checking out the Best-README-Template. If you have a suggestion
*** that would make this better, please fork the repo and create a pull request
*** or simply open an issue with the tag "enhancement".
*** Thanks again! Now go create something AMAZING! :D
-->



<!-- PROJECT SHIELDS -->
<!--
*** I'm using markdown "reference style" links for readability.
*** Reference links are enclosed in brackets [ ] instead of parentheses ( ).
*** See the bottom of this document for the declaration of the reference variables
*** for contributors-url, forks-url, etc. This is an optional, concise syntax you may use.
*** https://www.markdownguide.org/basic-syntax/#reference-style-links
-->
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]



<!-- PROJECT LOGO -->
<br />
<p align="center">
  <!--<a href="https://github.com/othneildrew/Best-README-Template">
    <img src="images/logo.png" alt="Logo" width="80" height="80">
  </a>-->

  <h3 align="center">EatMan</h3>

  <p align="center">
    Setup your own meal planner.
    <br />
    <a href="https://github.com/Kreisverkehr/EatMan"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/Kreisverkehr/EatMan/issues">Report Bug</a>
    ·
    <a href="https://github.com/Kreisverkehr/EatMan/issues">Request Feature</a>
  </p>
</p>



<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

[![Product Name Screen Shot][product-screenshot]]

Have you ever faced the problem that you do not know what to cook today? Have you then thought for hours and rummaged through cookbooks? This is where EatMan comes in. He takes the decision away from you by making a random suggestion. Only dishes that EatMan knows and that you have entered yourself are taken into account.

### Built With

* [Bootstrap](https://getbootstrap.com)
* [JQuery](https://jquery.com)
* [Composer](https://getcomposer.org)
* [Font Awesome](https://fontawesome.com)
* [Mustache](https://mustache.github.io)

<!-- GETTING STARTED -->
## Getting Started

To get a local copy up and running follow these simple example steps.

### Prerequisites

This is an example of how to list things you need to use the software and how to install them.
* git
* apache
* php
* mysql
  ```sh
  sudo apt install git apache2 mysql-server php php-mysqli
  ```

### Installation

#### Option 1: Run the automated installer
```sh
curl -s https://raw.githubusercontent.com/Kreisverkehr/EatMan/main/src/setup/install.sh | bash -s
```

#### Option 2: Manual install

1. Clone the repo
   ```sh
   cd /opt/
   git clone https://github.com/Kreisverkehr/EatMan.git
   ```
2. Install Composer
   ```sh
   cd EatMan/src/wwwroot
   ./../setup/installcomposer.sh
   ```
   https://getcomposer.org/download/
3. Install Dependencies
   ```sh
   php composer.phar install
   ```
4. Create Database
   ```sh
   sudo mysql -e "CREATE DATABASE EatMan"
   sudo mysql EatMan < ../setup/createdb.sql
   ```
5. Enter your mysql user in `src/wwwroot/sys/settings.php`
   ```php
   <?php
   $db_user = "mysql_user";
   $db_pass = "mysql_super_secret_password";
   ```
6. Create symlink
   ```sh
   sudo ln -s /opt/EatMan/src/wwwroot/ /var/www/html/EatMan
   ```


<!-- USAGE EXAMPLES -->
## Usage

* Click on "Add dish" do start adding your dishes.
* You are now ready to get suggestions from the start page. Just click "Make a suggestion!"



<!-- ROADMAP -->
## Roadmap

See the [open issues](https://github.com/Kreisverkehr/EatMan/issues) for a list of proposed features (and known issues).



<!-- CONTRIBUTING -->
## Contributing

Contributions are what make the open source community such an amazing place to be learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request



<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.



<!-- CONTACT -->
## Contact

Project Link: [https://github.com/Kreisverkehr/EatMan](https://github.com/Kreisverkehr/EatMan)


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/Kreisverkehr/EatMan.svg?style=for-the-badge
[contributors-url]: https://github.com/Kreisverkehr/EatMan/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/Kreisverkehr/EatMan.svg?style=for-the-badge
[forks-url]: https://github.com/Kreisverkehr/EatMan/network/members
[stars-shield]: https://img.shields.io/github/stars/Kreisverkehr/EatMan.svg?style=for-the-badge
[stars-url]: https://github.com/Kreisverkehr/EatMan/stargazers
[issues-shield]: https://img.shields.io/github/issues/Kreisverkehr/EatMan.svg?style=for-the-badge
[issues-url]: https://github.com/Kreisverkehr/EatMan/issues
[license-shield]: https://img.shields.io/github/license/Kreisverkehr/EatMan.svg?style=for-the-badge
[license-url]: https://github.com/Kreisverkehr/EatMan/blob/main/LICENSE
[product-screenshot]: images/Homepage.png
