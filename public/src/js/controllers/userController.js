import {RequestService} from "../services/request.service.js";
import {Routes} from "../routing/routes.js";

//
export let UserController = {
    addUser: function (e) {
        let form = e.target || e.srcElement;
        RequestService.send('POST', Routes.user.add, {
            email: form.elements.email.value,
            password: form.elements.password.value
        }, (response) => {
            if (response.status === 'ok') {
                alert(
                    "Added new user:\n\n" +
                    "Id: " + response.user.id + "\n" +
                    "Email: " + response.user.email + "\n" +
                    "Password: " + response.user.password + "\n"
                );
            } else if (response.status === 'error') {
                alert(
                    "Error adding user!\n\n" + response.errorMessage ? response.errorMessage : ''
                );
            }
        });
    },

    getUser: function (e) {
        let form = e.target || e.srcElement;
        RequestService.send('GET', Routes.user.get + form.elements.search.value, {}, (response) => {
            if (response.status === 'ok') {
                alert(
                    "User with id " + form.elements.search.value + ":\n\n" +
                    "Email: " + response.user.email + "\n" +
                    "Password: " + response.user.password + "\n"
                );
            } else if (response.status === 'error') {
                alert(
                    "Error getting user!\n\n" + response.errorMessage ? response.errorMessage : ''
                );
            }
        });
    },

    deleteUser: function (e) {
        let form = e.target || e.srcElement;
        RequestService.send('DELETE', Routes.user.delete + form.elements.id.value, {}, (response) => {
            if (response.status === 'ok') {
                alert(
                    "User with id " + form.elements.id.value + "was deleted!"
                );
            } else if (response.status === 'error') {
                alert(
                    "Error deleting user!\n\n" + response.errorMessage ? response.errorMessage : ''
                );
            }
        });
    }
};