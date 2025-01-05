/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector',
  // content: ["./*.{html,js,php}", "./**/*.{html,js,php}"],
  content: ["./*.php", "./**/*.php", "./**/**/*.php", "./includes/*.php", "./calculator/*.php"],
   theme: {
     extend: {},
},
   plugins: [],
}

