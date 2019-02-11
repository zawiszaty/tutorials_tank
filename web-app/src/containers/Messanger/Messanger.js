import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Paper from "@material-ui/core/Paper";
import CircularProgress from "@material-ui/core/CircularProgress";
import CssBaseline from "@material-ui/core/CssBaseline";
import {withStyles} from "@material-ui/core";
import axios from "../../axios/axios";
import Typography from "@material-ui/core/Typography";
import {login} from "../../actions/user";
import {connect} from "react-redux";
import CategoryList from "../../components/Post/CategoryList";
import MessangerComponent from "../../components/Messanger/MessangerComponent";

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
            width: '80%',
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

class Messanger extends Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            messages: {},
            error: false,
        }
    }

    getAllMessages = () => {
        axios.get(`/api/v1/message?recipient=${this.props.match.params.id}`,{
            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
        })
            .then((response) => {
                const messages = response.data.data;
                const loading = false;
                this.setState({
                    messages,
                    loading
                });
            })
            .catch((e) => {
                if (e.response.status === 404) {
                    this.setState({
                        loading: false,
                        messages: [],
                    });
                } else {
                    const error = false;
                    const loading = false;
                    this.setState({
                        error,
                        loading
                    });
                }
            })
    };

    componentDidMount = () => {
        this.getAllMessages();
    };

    render() {
        const {classes} = this.props;

        if (this.state.loading === false) {
            return (
                <React.Fragment>
                    <MessangerComponent messages={this.state.messages} id={this.props.match.params.id}/>
                </React.Fragment>
            );
        }

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

Messanger.propTypes = {};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};
const mapDispatchToProps = {login};

export default connect(mapStateToProps, mapDispatchToProps)(withStyles(styles)(Messanger));