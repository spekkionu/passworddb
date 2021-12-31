function updateOptions(options) {
    const update = {...options};

    update.headers = {
        'X-Requested-With': 'XMLHttpRequest',
        ...update.headers
    };

    return update;
}

export default function fetcher(url, options) {
    return fetch(url, updateOptions(options));
}
