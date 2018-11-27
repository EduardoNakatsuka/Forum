
let user = _.get(window, 'App.user', -1);

let authorizations ={
    owns(model, prop = 'user_id') {
        return model[prop] === user.id;
    },

    isAdmin () {
        return ['JohnDoe', 'JaneDoe'].includes(user.name);
    }
};

module.exports = authorizations;