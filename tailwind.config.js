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
    "./crud/**/*.php",
    "./crud/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
