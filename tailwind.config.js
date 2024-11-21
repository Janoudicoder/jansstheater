module.exports = {
	purge: ['/', 'php/*.php', 'php/blocks/*.php', 'php/overzichten/*.php', 'php/headers/*.php', 'php/contact/*.php', 'php/realworks/*.php'],
	theme: {
		screens: {
			sm: '480px',
			md: '768px',
			lg: '976px',
			xl: '1200px',
			xxl: '1440px',
		},
		fontFamily: {
			serif: ['var(--bodyFont)', 'var(--bodyFontType)'],
		},
		extend: {
			fontFamily: {
				kop: ['var(--headingFont)', 'var(--headingFontType)'],
				sitework: ['var(--sitework)', 'var(--siteworkType)'],
			},
			colors: {
				primary: 'var(--primary)',
				secondary: 'var(--secondary)',
				zwart: 'var(--backBlack)',
				text: 'var(--text)',
				lichtGrijs: 'var(--lightGray)',
			},
		},
	},
	variants: {},
	plugins: [],
};
