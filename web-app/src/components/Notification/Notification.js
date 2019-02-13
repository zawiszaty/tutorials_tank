import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import { connect } from 'react-redux';
import { login } from "../../actions/user";
import { toast } from "react-toastify";

const styles = theme => ({

});

class Notification extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
        };
    }

    notify = () => {
        this.onNotify();
    };

    onNotify = () => {
        let then = this;
        const ab = window.ab;
        // var userid = this.state.user;
        var conn = new ab.Session(`ws://localhost:8888?token=${localStorage.getItem('token')}`,
            function () {
                console.log('dziala');
                conn.subscribe('1', function (topic, data) {
                    console.log('dziala');
                    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                    toast.success("Masz nowe powiadomienie", {
                        position: toast.POSITION.BOTTOM_RIGHT
                    });
                    console.log('New article published to category "' + topic + '" : ' + data.title);
                });
            },
            function () {
                console.warn('WebSocket connection closed');
            },
            { 'skipSubprotocolCheck': true }
        );
    };
    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline />
                {this.props.user.length !== 0 && <React.Fragment>
                    {this.notify()}
                </React.Fragment>}
            </React.Fragment>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = { login };

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(Notification));