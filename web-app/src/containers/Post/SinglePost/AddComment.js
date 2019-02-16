import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Button from '@material-ui/core/Button';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../../axios/axios';
import CircularProgress from "@material-ui/core/CircularProgress";
import renderHTML from 'react-render-html';
import {Link as RouterLink} from "react-router-dom";
import {connect} from "react-redux";
import {ValidatorForm} from "react-material-ui-form-validator";
import {Editor} from "@tinymce/tinymce-react/lib/es2015";
import classNames from "classnames";
import green from "@material-ui/core/colors/green";
import {toast} from "react-toastify";

function TabContainer({children, dir}) {
    return (
        <Typography component="div" dir={dir} style={{padding: 8 * 3}}>
            {children}
        </Typography>
    );
}

const styles = theme => ({
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: "80%",
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
    paper__content: {
        marginTop: theme.spacing.unit * 8,
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    paper__content__oder: {
        marginTop: theme.spacing.unit * 8,
        minHeight: "100vh",
    },
    root: {
        backgroundColor: theme.palette.background.paper,
        width: 500,
    },
    body__iframe: {
        height: "100vh",
        width: "100%",
    },
    form: {
        width: '100%', // Fix IE 11 issue.
        marginTop: theme.spacing.unit,
    },
    wrapper: {
        margin: theme.spacing.unit,
        position: 'relative',
        display: 'flex',
        alignItems: 'center',
    },
    buttonSuccess: {
        backgroundColor: green[500],
        '&:hover': {
            backgroundColor: green[700],
        },
    },
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
});

class Comment extends Component {
    state = {
        content: '',
        loading: false,
        error: false,
    };

    handleSubmit = () => {
        this.setState({
            loading: true,
        });
        if (this.state.content !== '') {
            axios.post('/api/v1/comment', {
                content: this.state.content,
                post: this.props.postId
            }, {
                headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
            }).then((response) => {
                toast.success("Dodano komentarz", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                this.setState({
                    loading: false,
                    content: '',
                });
                this.props.getAllComments();
            }).catch((e) => {
                toast.error("Coś poszło nie tak", {
                    position: toast.POSITION.BOTTOM_RIGHT
                });
                this.setState({
                    loading: false,
                });
            })
        } else {
            this.setState({
                loading: false,
            });
            toast.info("Pole jest puste", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
        }

    };

    handleChangeContent = (event) => {
        const content = event.target.getContent();
        this.setState({content});
        console.log(this.state.content);
    };

    render() {
        const {classes, theme} = this.props;
        const buttonClassname = classNames({
            [classes.buttonSuccess]: this.state.success,
        });
        return (
            <main className={classes.main}>
                <Paper className={classes.paper}>
                    <ValidatorForm
                        ref="form"
                        onSubmit={this.handleSubmit}
                        className={classes.form}
                    >
                        <Editor
                            apiKey="69sluxkknib3n831hobh8k54b5yjjvzaexa4hutx9liz6l2b"
                            value={this.state.content}
                            init={{
                                plugins: 'link code',
                                toolbar: 'undo redo | bold italic | code'
                            }}
                            onChange={this.handleChangeContent}
                        />
                        <div className={classes.wrapper}>
                            <Button
                                variant="contained"
                                color="primary"
                                type="submit"
                                disabled={this.state.loading}
                                fullWidth
                                className={buttonClassname}
                            >
                                Skomentuj
                            </Button>
                            {this.state.loading && <CircularProgress size={24} className={classes.buttonProgress}/>}
                        </div>
                    </ValidatorForm>
                </Paper>
            </main>
        );
    }
}

Comment.propTypes = {
    classes: PropTypes.object.isRequired,
};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};

export default connect(mapStateToProps)(withStyles(styles, {withTheme: true})(Comment));