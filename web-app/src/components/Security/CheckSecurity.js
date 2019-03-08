import React, {Component} from 'react';
import PropTypes from 'prop-types';
import CircularProgress from '@material-ui/core/CircularProgress';
import {withStyles} from '@material-ui/core/styles';
import {login} from "../../actions/user";
import {getNotification} from "../../actions/notification";
import {connect} from "react-redux";
import axios from "../../axios/axios";
import {toast} from "react-toastify";
import Paper from "@material-ui/core/Paper";
import CssBaseline from "@material-ui/core/CssBaseline";

const styles = theme => ({
    progress: {
        margin: theme.spacing.unit * 2,
    },
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: 400,
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    avatar: {
        margin: theme.spacing.unit,
        backgroundColor: theme.palette.secondary.main,
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

class CheckSecurity extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount = () => {
        this.check();
    };

    check = () => {
        let token = localStorage.getItem('token');
        axios.post('/api/v1/seciurity', {}, {
            headers: {'Authorization': 'Bearer ' + token}
        }).then((response) => {
            localStorage.setItem('token', token);
            this.props.login(response.data);
            this.getAllNotification();
            this.props.loaded();
        }).catch((e) => {
            this.props.loaded();
        });
    };

    getAllNotification = () => {
        axios.get(`/api/v1/notifications/total`, {
            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
        })
            .then((e) => {
                this.props.getNotification(e.data);
                document.title = `Tutorials Tank (${e.data})`;
            }).catch((e) => {
            this.props.getNotification(0);
            document.title = `Tutorials Tank (0)`;
        });
    };

    render() {
        const {classes} = this.props;
        return (
            <div>
                <main className={classes.main}>
                    <CssBaseline/>
                    <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper>
                </main>
            </div>
        );
    }
}

CheckSecurity.propTypes = {
    classes: PropTypes.object.isRequired,
};

const mapStateToProps = (state) => {
    return {
        user: state.user,
        notification: state.notification
    }
};
const mapDispatchToProps = {login, getNotification};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(CheckSecurity));