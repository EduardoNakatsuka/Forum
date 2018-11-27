
let user = _.get(window, 'App.user.id', -1);

let authorizations ={
    owns(model, prop = 'user_id') {
        return model[prop] === user;
    }

};

module.exports = authorizations;