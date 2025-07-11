/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    "./app/**/*.{php,html,js}",
    "node_modules/preline/dist/*.js"
  ],
  safelist: [
    'w-6', 'h-6', 'w-8', 'h-8',
    'text-gray-700', 'text-gray-500', 'text-gray-900',
    'rounded-full', 'hover:bg-gray-200',
    'focus:outline-none', 'focus:ring-1', 'focus:ring-gray-300',
    'absolute', 'right-0', 'mt-2', 'bg-white', 'border', 'shadow-lg',
    'text-sm', 'px-4', 'py-2'
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('preline/plugin')
  ],
}
