import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import {withSnackbar} from "notistack";
import {connect} from 'react-redux';
import {loginUser} from "../../actions/user-action";

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
        // var userid = this.state.user;
        var conn = new ab.Session(`ws://localhost:8888?token=${localStorage.getItem('token')}`,
            function() {
                conn.subscribe(localStorage.getItem('userId'), function(topic, data) {
                    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                    then.props.onPresentSnackbar('info', 'Dodano notke');
                    console.log('New article published to category "' + topic + '" : ' + data.title);
                });
            },
            function() {
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
                {this.props.user.length !== 0 && this.notify()}
            </React.Fragment>
        );
    }
}

const mapStateToProps = state => ({
    user: state.user
});

const mapActionToProps = {
    onLoginUser: loginUser
};

export default connect(mapStateToProps, mapActionToProps)(withSnackbar(withStyles(styles)(Notification)));