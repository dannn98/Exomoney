export default function authHeader() {
    let access_token = localStorage.getItem('access_token')

    if (access_token) {
        return 'Bearer ' + access_token
    }
    return {}
}