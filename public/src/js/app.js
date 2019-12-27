//Web app starting point
;import {UserController} from "./controllers/userController.js";

let _dexApp = {
    UserController: UserController
};
//All app functional can be accessed in window.dexApp
window.dexApp = _dexApp;