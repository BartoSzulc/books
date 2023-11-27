// https://tailwindcss.com/docs/configuration
const plugin = require('tailwindcss/plugin')

module.exports = {
  content: ["./src/templates/**/*.{php,vue,js}"],
  theme: {
    fontFamily: {
      'montserrat': ['Montserrat', 'sans-serif'],
      
    }
  },
  plugins: [
    plugin(function ({ addComponents }) {
      addComponents({
        ".container": {
          paddingLeft: "1.25rem",
          paddingRight: "1.25rem",
          width: "100%",
          maxWidth: "1480px",
          margin: "0 auto",
        },
        ".wrapper": {
          paddingLeft: "1.25rem",
          paddingRight: "1.25rem",
        },
  
      })
    }),
  ],
};