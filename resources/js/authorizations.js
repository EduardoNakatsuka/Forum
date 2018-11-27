
let user = _.get(window, 'App.user.id', -1);

let authorizations ={
    updateReply (reply) {
        return reply.user_id === user;
    }
};

module.exports = authorizations;