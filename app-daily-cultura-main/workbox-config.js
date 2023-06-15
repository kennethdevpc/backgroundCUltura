module.exports = {
	globDirectory: 'public/',
	globPatterns: [
		'**/*.{ico,png,json}'
	],
	swDest: 'public/sw.js',
	ignoreURLParametersMatching: [
		/^utm_/,
		/^fbclid$/
	]
};