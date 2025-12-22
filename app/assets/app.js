import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//import "./styles/app.css";
//import "./styles/layout/header.css";


switch (window.location.pathname) {
  case "/":
    import('./styles/Pages/homePage.css');
    break;
//   case "/contact":
//     import('./styles/_partials/contact.css');
//     break;

}

   