import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
const light = {

    dark: false,
    colors: {
        background: '#FFFFFF',
        surface: '#FFFFFF',
        primary: 'red',
        'primary-darken-1': '#3700B3',
        secondary: '#03DAC6',
        'secondary-darken-1': '#018786',
        error: '#B00020',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FB8C00',
    }
}
const dark = {
    dark: true,
    colors: {
        surface: '#000000',
        primary: '#000000',
        'primary-darken-1': '#000000',
        secondary: '#000000',
        'secondary-darken-1': '#000000',
        error: '#000000',
        info: '#000000',
        success: '#000000',
        warning: '#000000',
    }
}
const vuetify = createVuetify({
    components,
    directives,
    icons: { defaultSet: 'mdi', aliases, sets: { mdi } },
    theme: {
        defaultTheme: 'light',
        themes: {
            light,
            dark,
        }
    }

})

export default vuetify;