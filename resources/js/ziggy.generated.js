const Ziggy = {
    url: 'http:\/\/localhost',
    port: null,
    defaults: {},
    routes: {
        'filament.exports.download': {
            uri: 'filament\/exports\/{export}\/download',
            methods: ['GET', 'HEAD'],
            parameters: ['export'],
            bindings: { export: 'id' },
        },
        'filament.imports.failed-rows.download': {
            uri: 'filament\/imports\/{import}\/failed-rows\/download',
            methods: ['GET', 'HEAD'],
            parameters: ['import'],
            bindings: { import: 'id' },
        },
        'filament.admin.auth.login': {
            uri: 'admin\/login',
            methods: ['GET', 'HEAD'],
        },
        'filament.admin.auth.logout': {
            uri: 'admin\/logout',
            methods: ['POST'],
        },
        'filament.admin.pages.dashboard': {
            uri: 'admin',
            methods: ['GET', 'HEAD'],
        },
        'filament.admin.resources.aspirasis.index': {
            uri: 'admin\/aspirasis',
            methods: ['GET', 'HEAD'],
        },
        'filament.admin.resources.aspirasis.edit': {
            uri: 'admin\/aspirasis\/{record}\/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['record'],
        },
        'filament.admin.resources.topiks.index': {
            uri: 'admin\/topiks',
            methods: ['GET', 'HEAD'],
        },
        'filament.admin.resources.topiks.create': {
            uri: 'admin\/topiks\/create',
            methods: ['GET', 'HEAD'],
        },
        'filament.admin.resources.topiks.edit': {
            uri: 'admin\/topiks\/{record}\/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['record'],
        },
        'sanctum.csrf-cookie': {
            uri: 'sanctum\/csrf-cookie',
            methods: ['GET', 'HEAD'],
        },
        'livewire.update': { uri: 'livewire\/update', methods: ['POST'] },
        'livewire.upload-file': {
            uri: 'livewire\/upload-file',
            methods: ['POST'],
        },
        'livewire.preview-file': {
            uri: 'livewire\/preview-file\/{filename}',
            methods: ['GET', 'HEAD'],
            parameters: ['filename'],
        },
        login: { uri: 'login', methods: ['GET', 'HEAD'] },
        dashboard: { uri: 'dashboard', methods: ['GET', 'HEAD'] },
        'aspirasi.vote': {
            uri: 'aspirasi\/{id}\/vote',
            methods: ['POST'],
            parameters: ['id'],
        },
        'aspirasi.create': {
            uri: 'aspirasi\/create',
            methods: ['GET', 'HEAD'],
        },
        'aspirasi.store': { uri: 'aspirasi', methods: ['POST'] },
        'aspirasi.edit': {
            uri: 'aspirasi\/{id}\/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['id'],
        },
        'aspirasi.update': {
            uri: 'aspirasi\/{id}',
            methods: ['PATCH'],
            parameters: ['id'],
        },
        'aspirasi.destroy': {
            uri: 'aspirasi\/{id}',
            methods: ['DELETE'],
            parameters: ['id'],
        },
        register: { uri: 'register', methods: ['GET', 'HEAD'] },
        logout: { uri: 'logout', methods: ['POST'] },
        'verification.notice': {
            uri: 'verify-email',
            methods: ['GET', 'HEAD'],
        },
        'verification.verify': {
            uri: 'verify-email\/{id}\/{hash}',
            methods: ['GET', 'HEAD'],
            parameters: ['id', 'hash'],
        },
        'verification.send': {
            uri: 'email\/verification-notification',
            methods: ['POST'],
        },
        'profile.edit': { uri: 'profile', methods: ['GET', 'HEAD'] },
        'profile.update': { uri: 'profile', methods: ['PATCH'] },
        'profile.destroy': { uri: 'profile', methods: ['DELETE'] },
        'aspirasi.export.pdf': {
            uri: 'aspirasi\/export\/pdf',
            methods: ['GET', 'HEAD'],
        },
        'aspirasi.export.excel': {
            uri: 'aspirasi\/export\/excel',
            methods: ['GET', 'HEAD'],
        },
        'password.request': {
            uri: 'forgot-password',
            methods: ['GET', 'HEAD'],
        },
        'password.email': { uri: 'forgot-password', methods: ['POST'] },
        'password.reset': {
            uri: 'reset-password\/{token}',
            methods: ['GET', 'HEAD'],
            parameters: ['token'],
        },
        'password.store': { uri: 'reset-password', methods: ['POST'] },
        'password.confirm': {
            uri: 'confirm-password',
            methods: ['GET', 'HEAD'],
        },
        'password.update': { uri: 'password', methods: ['PUT'] },
        'storage.local': {
            uri: 'storage\/{path}',
            methods: ['GET', 'HEAD'],
            wheres: { path: '.*' },
            parameters: ['path'],
        },
    },
};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
