import http from 'k6/http';
import { check, sleep } from 'k6';
export const options = {
    vus: 100,
    duration: '30s',
};
export default function () {
    const res = http.get('http://localhost:8000/api/store');
    check(res, {
        'status code 200': (r) =>  r.status === 200,
        'response time < 300ms': (r) => r.timings.duration < 500,
    });
    // sleep(1);
}