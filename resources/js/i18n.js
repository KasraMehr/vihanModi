import { createI18n } from 'vue-i18n';
import fa from './lang/fa.json';
import en from './lang/en.json';
import ar from './lang/ar.json';

const locale = localStorage.getItem('locale') || 'fa';

const i18n = createI18n({
    legacy: false,
    locale: locale,
    fallbackLocale: 'en',
    messages: { fa, en, ar }
});

export default i18n;
