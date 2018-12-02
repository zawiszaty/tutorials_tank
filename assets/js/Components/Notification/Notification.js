import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import {withSnackbar} from "notistack";

const styles = theme => ({

});

class Notification extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    componentDidMount = () => {
        this.onNotify();
    };

    onNotify = () => {
        var conn = new ab.Session(`ws://0.0.0.0:8888?token=${localStorage.getItem('token')}`,
            () => {
                conn.publish('user', (topic, data)  => {
                    this.props.onPresentSnackbar('success', 'test')
                });
            } ,
            () => {
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

export default withSnackbar(withStyles(styles)(Notification));