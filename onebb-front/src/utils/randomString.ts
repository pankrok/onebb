export default function randomString() {
    return (Math.random() + 1).toString(36).substring(3);
}