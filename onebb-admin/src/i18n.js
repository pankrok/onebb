import  { createI18n } from 'vue-i18n'

function initLocale() {
    if(localStorage.getItem('locale') == null) {
        localStorage.setItem('locale', document.getElementById('app').dataset.locale);
    }
}

/**
 * Load locale messages
 *
 * The loaded `JSON` locale messages is pre-compiled by `@intlify/vue-i18n-loader`, which is integrated into `vue-cli-plugin-i18n`.
 * See: https://github.com/intlify/vue-i18n-loader#rocket-i18n-resource-pre-compilation
 */
function loadLocaleMessages() {
  const locales = require.context('./locales/', true, /[A-Za-z0-9-_,\s]+\.json$/i)
  const messages = {}
  locales.keys().forEach(key => {
    const matched = key.match(/([A-Za-z0-9-_]+)\./i)
    if (matched && matched.length > 1) {
      const locale = matched[1]
      messages[locale] = locales(key)
    }
  });
  return messages
}
initLocale();
const i18n = createI18n({
  locale: localStorage.getItem('locale'),
  messages: loadLocaleMessages()  
})

export default i18n;