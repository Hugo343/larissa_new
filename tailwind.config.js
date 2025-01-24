/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{html,js,php}",
    "./src/*.css",
  ],
  theme: {
    extend: {
      colors: {
        primaryColor: '#e5dbc3',
        secondaryColor: '#c7ad7f',
      },
    },
    fontFamily: {
      display: ["Nunito", "sans-serif",
      "montserrat", "Montserrat",
      "Dancing Script", "cursive"],
    },
  },
  plugins: [],
}