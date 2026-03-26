/**
 * Shared formatting utilities for the Course Hour Tracker.
 * All dates are displayed in IST (Asia/Kolkata, UTC+5:30).
 */

const IST_LOCALE = 'en-IN';
const IST_TZ = 'Asia/Kolkata';

/**
 * Format a UTC/ISO date string to DD-MM-YYYY HH:mm in IST.
 * @param {string|null} dateStr
 * @returns {string}
 */
export const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const opts = {
        timeZone: IST_TZ,
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    };
    // Intl produces "DD/MM/YYYY, HH:mm" for en-IN; reshape to "DD-MM-YYYY HH:mm"
    const parts = new Intl.DateTimeFormat(IST_LOCALE, opts).formatToParts(d);
    const get = (type) => parts.find(p => p.type === type)?.value ?? '';
    return `${get('day')}-${get('month')}-${get('year')} ${get('hour')}:${get('minute')}`;
};

/**
 * Format minutes into human-readable duration.
 * @param {number} m  minutes
 * @returns {string}
 */
export const formatDuration = (m) => {
    m = Number(m);
    if (m < 60) return `${m} min`;
    const h = Math.floor(m / 60);
    const rem = m % 60;
    return rem > 0 ? `${h} hr ${rem} min` : `${h} hr`;
};
