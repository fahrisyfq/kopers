/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue", // (Bisa ditambahkan jika Anda pakai Vue.js)
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php", // (Penting untuk styling pagination)
    ],
    theme: {
        extend: {}, 
    },
    plugins: [require('@tailwindcss/line-clamp')],
};
