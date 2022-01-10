export default function authHeader(contentType = 'json') {
    let access_token = localStorage.getItem('access_token')

    if (access_token) {
        return {
            Authorization: 'Bearer ' + access_token,
            'Content-Type': contentType
        }
    }
    return {
        'Content-Type': contentType
    }
}