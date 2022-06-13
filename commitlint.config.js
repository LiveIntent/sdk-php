module.exports = {
    extends: ["@commitlint/config-conventional"],
    rules:{
        'subject-case': [0, 'never'],
        'scope-case': [2, 'always', 'upper-case'],
    },
    ignores: [
        (message) => message.includes('WIP'),
        (message) => message.includes('Initial commit'),
    ]
};
