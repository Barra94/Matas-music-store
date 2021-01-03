

const API_TOKEN = 'api_token';

import {eventBus} from "./app";

export function login(token) {
    localStorage.setItem(API_TOKEN, token);
    eventBus.changeApiToken(token);
}

export function logout() {
    localStorage.removeItem(API_TOKEN);
    eventBus.changeApiToken("");
}

export function getApiToken() {
    return localStorage.getItem(API_TOKEN);
}

function clearApiToken() {
    localStorage.removeItem(API_TOKEN);
    eventBus.changeApiToken("");
}

export function setApiToken(token) {
    localStorage.setItem(API_TOKEN, token);
    eventBus.changeApiToken(token);
}
