import { createI18n } from 'vue-i18n'

function initLocale() {
  if (localStorage.getItem('locale') == null) {
    localStorage.setItem('locale', 'en')
  }
}

function loadLocaleMessages() {
  // @ts-ignore
  const locales = import.meta.glob('./../locales/*.json', {as: 'json', eager: true, import: 'default' })
  console.log({locales})
  const messages: {[key: string]: object} = {} 
  for (const path in locales) {
       const matched = path.match(/([A-Za-z0-9-_]+)\./i)
       if (matched && matched.length > 1) {
            const locale = matched[1] // @ts-ignore
            messages[locale] = locales[path];
        }
  }
  console.log({messages})
  return messages

}
initLocale()

// @ts-ignore
const i18n = createI18n({
  useScope: 'global',
  globalInjection: true,
  legacy: false,
  locale: localStorage.getItem('locale'),
  messages: loadLocaleMessages()
})

export default i18n;
export const { t: $t } = i18n.global;