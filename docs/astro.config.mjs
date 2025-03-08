// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

import mdx from '@astrojs/mdx';
import markdoc from '@astrojs/markdoc';

// https://astro.build/config
export default defineConfig({
	integrations: [
		starlight({
			title: 'NaN',
			description: 'NaN micro-framework',
			social: {
				github: 'https://github.com/AimForNaN/nan',
			},
			sidebar: [
				{
					label: 'Getting started',
					autogenerate: {directory: 'getting-started'},
				},
				{
					label: 'Components',
					autogenerate: {directory: 'components'},
				},
			],
		}),
		markdoc(),
		mdx(),
	],
});