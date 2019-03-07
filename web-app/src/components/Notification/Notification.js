import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import {connect} from 'react-redux';
import {login} from "../../actions/user";
import {toast} from "react-toastify";
import {getNotification} from "../../actions/notification";
import {baseSocket} from "../../axios/env";

const styles = theme => ({});

class Notification extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            working: false,
        };
    }

    notify = () => {
        if(this.state.working === false) {
            this.setState({
                working: true,
            }, () => {
                this.onNotify();
            })
        }
    };

    componentWillReceiveProps = (nextProps, nextContext) => {
        if (this.props.user.length !== 0) {
            this.notify()
        }
    };

    onNotify = () => {
        let then = this;
        const ab = window.ab;
        // var userid = this.state.user;
        var conn = new ab.Session(`ws://${baseSocket}:8888?token=${localStorage.getItem('token')}`,
            function () {
                conn.subscribe(then.props.user[0].id, function (topic, data) {
                    let total = parseInt(then.props.notification);
                    then.props.getNotification(total + 1);
                    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                    toast.success("Masz nowe powiadomienie", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                });
            },
            function () {
                console.warn('WebSocket connection closed');
            },
            {'skipSubprotocolCheck': true}
        );
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
            </React.Fragment>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user,
        notification: state.notification
    }
};
const mapDispatchToProps = {login, getNotification};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(Notification));