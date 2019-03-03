import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Button from '@material-ui/core/Button';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../../axios/axios';
import CircularProgress from "@material-ui/core/CircularProgress";
import renderHTML from 'react-render-html';
import {Link as RouterLink, withRouter} from "react-router-dom";
import {connect} from "react-redux";
import Comment from "./Comment";
import AddComment from "./AddComment";
import {toast} from "react-toastify";
import ModalSinglePost from "./ModalSinglePost";

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
});

class SinglePost extends Component {
    state = {
        post: [],
        loading: true,
        openModal: false,
    };

    componentDidMount = () => {
        this.getAllPost();
    };

    componentWillReceiveProps(nextProps, nextContext) {
        this.getAllPost();
    }

    getAllPost = () => {
        this.setState({
            loading: true
        });
        axios.get(`/api/v1/post/slug/${this.props.match.params.slug}`).then((e) => {
            this.setState({
                post: e.data,
                loading: false
            });
        })
            .catch((e) => {

            })
    };

    handleCloseModal = () => {
        this.setState({
            openModal: false,
        })
    };


    render() {
        const {classes, theme} = this.props;

        return (
            <main className={classes.main}>
                {this.state.loading === true ? <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper> :
                    <React.Fragment>
                        {this.props.user.length !== 0 &&
                        <React.Fragment>
                            {this.state.post.user === this.props.user[0].id ?
                                <React.Fragment>
                                    <Button
                                        variant="outlined"
                                        color="primary"
                                        fullWidth
                                        className={classes.paper}
                                        component={RouterLink} to={"/edytuj/post/" + this.state.post.slug}
                                    >
                                        Edytuj
                                    </Button>
                                    <Button
                                        variant="outlined"
                                        color="secondary"
                                        fullWidth
                                        className={classes.paper}
                                        onClick={(e) => {
                                            this.setState({
                                                openModal: true,
                                            })
                                        }}
                                    >
                                        Usuń
                                    </Button>
                                    <ModalSinglePost open={this.state.openModal} id={this.state.post.id}
                                                     handleClose={this.handleCloseModal}/>
                                </React.Fragment>
                                :
                                <React.Fragment>
                                    {this.props.user[0].roles.includes('ROLE_ADMIN') &&
                                    <React.Fragment>
                                        <Button
                                            variant="outlined"
                                            color="primary"
                                            fullWidth
                                            className={classes.paper}
                                            component={RouterLink} to={"/edytuj/post/" + this.state.post.slug}
                                        >
                                            Edytuj
                                        </Button>
                                        <Button
                                            variant="outlined"
                                            color="secondary"
                                            fullWidth
                                            className={classes.paper}
                                            onClick={(e) => {
                                                this.setState({
                                                    openModal: true,
                                                })
                                            }}
                                        >
                                            Usuń
                                        </Button>
                                        <ModalSinglePost open={this.state.openModal} id={this.state.post.id} handleClose={this.handleCloseModal}/>
                                    </React.Fragment>
                                    }
                                </React.Fragment>
                            }
                        </React.Fragment>
                        }
                        <Paper className={classes.paper}>
                            <Typography variant="title" gutterBottom>
                                {this.state.post.title}
                            </Typography>
                        </Paper>
                        {this.state.post.type === "own_post" &&
                        <Paper className={classes.paper__content}>
                            <Typography variant="body1" gutterBottom>
                                {renderHTML(this.state.post.content)}
                            </Typography>
                        </Paper>
                        }
                        {this.state.post.type === "oder_site" &&
                        <React.Fragment>
                            <Paper className={classes.paper}>
                                <Button
                                    variant="contained"
                                    color="link"
                                    fullWidth
                                    className={classes.button}
                                >
                                    <a href={this.state.post.content} target="_blank">
                                        Odwiedź strone
                                    </a>
                                </Button>
                            </Paper>
                            <Paper className={classes.paper__content__oder}>
                                <iframe src={this.state.post.content} className={classes.body__iframe} frameBorder="0"/>
                            </Paper>
                        </React.Fragment>
                        }
                        <Comment postId={this.state.post.id}/>
                    </React.Fragment>
                }
            </main>
        );
    }
}

SinglePost.propTypes = {
    classes: PropTypes.object.isRequired,
};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};

export default withRouter(connect(mapStateToProps)(withStyles(styles, {withTheme: true})(SinglePost)));