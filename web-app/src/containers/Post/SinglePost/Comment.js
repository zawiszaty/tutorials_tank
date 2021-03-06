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
import Avatar from "@material-ui/core/es/Avatar/Avatar";
import Grid from "@material-ui/core/Grid";
import AddComment from "./AddComment";
import DeleteCommentModal from "./DeleteCommentModal";
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
    avatar: {
        width: "5em",
        height: "5em",
    },
    user: {
        display: "flex",
        textAlign: "center",
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
    },
    comment: {
        marginTop: theme.spacing.unit * 8,
        display: "flex",
        textAlign: "center",
        flexDirection: 'column',
        alignItems: 'center',
    },
    button: {
        marginTop: '3em',
    }
});

class Comment extends Component {
    constructor(props) {
        super(props);
        this.state = {
            comments: [],
            loading: true,
            error: false,
            count: 0,
            limit: 10,
        };
    }


    componentDidMount = () => {
        this.getAllComments();
    };

    getAllComments = () => {
        this.setState({
            loading: true
        });
        axios.get(`/api/v1/comments/${this.props.postId}?limit=${this.state.limit}`).then((e) => {
            this.setState({
                comments: e.data.data,
                count: e.data.total,
                loading: false
            });
        })
            .catch((e) => {
                this.setState({
                    error: true,
                    loading: false
                });
            })
    };


    render() {
        const {classes, theme} = this.props;

        return (
            <main className={classes.main}>
                {this.props.user.length !== 0 &&
                <React.Fragment>
                    <AddComment postId={this.props.postId} getAllComments={this.getAllComments}/>
                </React.Fragment>
                }
                <Paper className={classes.comment}>
                    <Typography variant="h3" gutterBottom>
                        Komentarze
                    </Typography>
                </Paper>
                {this.state.loading === true ? <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper> :
                    <React.Fragment>
                        {this.state.error === false ?
                            <React.Fragment>
                                {this.state.comments.map((comment) => {
                                    return (
                                        <Paper container className={classes.paper}>
                                            <Grid item xs={12} md={4} className={classes.user}>
                                                <Avatar src={comment.user.avatar}
                                                        className={classes.avatar}/>
                                                <Typography variant="title">
                                                    {comment.user.username}
                                                </Typography>
                                            </Grid>
                                            <Grid item xs={12} md={8}>
                                                {renderHTML(comment.content)}
                                            </Grid>
                                            {this.props.user.length !== 0 &&
                                            <React.Fragment>
                                                {this.props.user[0].name === comment.user.username ?
                                                    <Grid item xs={12}>
                                                        <DeleteCommentModal id={comment.id}
                                                                            getAllComments={this.getAllComments}/>
                                                    </Grid> :
                                                    <React.Fragment>
                                                        {this.props.user[0].roles.includes('ROLE_ADMIN') &&
                                                        <Grid item xs={12}>
                                                            <DeleteCommentModal id={comment.id}
                                                                                getAllComments={this.getAllComments}/>
                                                        </Grid>
                                                        }
                                                    </React.Fragment>
                                                }
                                            </React.Fragment>}
                                        </Paper>
                                    )
                                })}
                            </React.Fragment> :
                            <React.Fragment>{}</React.Fragment>
                        }
                        <Button
                            variant="outlined"
                            color="primary"
                            fullWidth
                            className={classes.button}
                            onClick={() => {
                                console.log(this.state.count, this.state.comments.length);
                                if (this.state.count === this.state.comments.length) {
                                    toast.info("Nie ma wiecej komentarzy", {
                                        position: toast.POSITION.BOTTOM_RIGHT
                                    });
                                } else {
                                    let limit = this.state.limit + 10;
                                    this.setState({limit}, () => {
                                        this.getAllComments();
                                    });
                                }
                            }}
                        >
                            Wiecej
                        </Button>
                    </React.Fragment>
                }
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