/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#008080', // Teal 
        secondary: {
          DEFAULT: '#6C757D', // Gray
          light: '#ADB5BD', // Light Gray
        },
        background: '#FFFFFF', // White
        success: '#28A745', // Green
        warning: '#FFC107', // Orange
        danger: '#DC3545', // Red
        text: {
          dark: '#343A40', // Dark Gray
          light: '#FFFFFF', // White
        }
      },
    },
  },
  plugins: [],
} 