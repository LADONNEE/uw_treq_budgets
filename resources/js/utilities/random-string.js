export default function(len) {
    len = len || 6;
    return [...Array(len)].map(i=>(~~(Math.random()*36)).toString(36)).join('');
}
