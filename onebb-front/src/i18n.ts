import { createI18n } from "vue-i18n";
function initLocale() {
  if (localStorage.getItem("locale") == null) {
    localStorage.setItem("locale", "pl");
  }
}

function loadLocaleMessages() {
  const locales = import.meta.globEager("./locales/*.json");
  const messages: any = {};
  for (const path in locales) {{
      const matched = path.match(/([A-Za-z0-9-_]+)\./i);
      if (matched && matched.length > 1) {
        const locale = matched[1];
        messages[locale] = locales[path];
      }
    };
  }
  console.log(messages)
  return messages;
}
initLocale();
const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem("locale") ?? "pl",
  messages: loadLocaleMessages(),
});

export default i18n;
