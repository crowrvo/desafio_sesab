import { Options } from 'unplugin-vue-components/types'

export default {
  dirs: ['src/components'],
  extensions: ['vue'],
  deep: true,
  types: [
    {
      from: 'vue-router',
      names: ['RouterLink', 'RouterView'],
    },
  ],
  directoryAsNamespace: false,
  allowOverrides: false,
  include: [/\.vue$/, /\.vue\?vue/],
  exclude: [/[\\/]node_modules[\\/]/, /[\\/]\.git[\\/]/, /[\\/]\.nuxt[\\/]/],
  version: 3,
} as Options
