const purgecss = require('@fullhuman/postcss-purgecss');
const cssnano = require('cssnano');

module.exports = {
	plugins: [
		require('postcss-import'),
		require('tailwindcss'),
		require('autoprefixer'),
		cssnano({
			preset: 'default',
		}),
		purgecss({
			content: ['**/*.php'],
			defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],
		}),
	],
};
