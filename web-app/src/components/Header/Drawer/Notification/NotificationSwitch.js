import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Comment from "./Type/Comment";
import Message from "./Type/Message";

class NotificationSwitch extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        if (this.props.notification.type === 'comment') {
            return (
                <div>
                    <Comment content={this.props.notification.content}/>
                </div>
            );
        } else if (this.props.notification.type === 'message') {
            return (
                <div>
                    <Message content={this.props.notification.content}/>
                </div>
            );
        } else {
            return (
                <div>
                    coś poszło nie tak
                </div>
            );
        }
    }
}

NotificationSwitch.propTypes = {};

export default NotificationSwitch;