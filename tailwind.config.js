/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "selector",
  content: [
    "./*.php",
    "./**/*.php",
    "./includes/*.php",
    "./calculator/*.php",
    "./public/js/*.js",
    "./public/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
