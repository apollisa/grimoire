const query = (url) => window.fetch(url, { headers: { "X-Stimulus": "true" } });

export default query;
