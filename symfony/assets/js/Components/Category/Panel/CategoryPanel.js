import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../../axios';
import {withSnackbar} from 'notistack';
import Table from '@material-ui/core/Table';
import TableBody from '@material-ui/core/TableBody';
import TableCell from '@material-ui/core/TableCell';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import TableFooter from '@material-ui/core/TableFooter';
import TablePagination from '@material-ui/core/TablePagination';
import CircularProgress from "@material-ui/core/CircularProgress/CircularProgress";
import IconButton from "@material-ui/core/IconButton";
import KeyboardArrowRight from '@material-ui/icons/KeyboardArrowRight';
import KeyboardArrowLeft from '@material-ui/icons/KeyboardArrowLeft';
import FirstPageIcon from '@material-ui/icons/FirstPage';
import LastPageIcon from '@material-ui/icons/LastPage';
import Button from '@material-ui/core/Button';
import {NavLink} from "react-router-dom";
import Drawer from "@material-ui/core/Drawer/Drawer";
import DeleteForeverOutlinedIcon from '@material-ui/icons/DeleteForeverOutlined';
import Modal from '@material-ui/core/Modal';
import CategoryPanelForm from './CategoryPanelForm';

const styles = theme => ({
    layout: {
        width: 'auto',
        display: 'block', // Fix IE11 issue.
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
        width: '100%', // Fix IE11 issue.
        marginTop: theme.spacing.unit,
    },
    submit: {
        marginTop: theme.spacing.unit * 3,
    },
});

const actionsStyles = theme => ({
    root: {
        flexShrink: 0,
        color: theme.palette.text.secondary,
        marginLeft: theme.spacing.unit * 2.5,
    },
});

class TablePaginationActions extends React.Component {
    constructor(props) {
        super(props);
        this.handleFirstPageButtonClick = this.handleFirstPageButtonClick.bind(this);
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
        this.handleNextButtonClick = this.handleNextButtonClick.bind(this);
        this.handleLastPageButtonClick = this.handleLastPageButtonClick.bind(this);
    }

    handleFirstPageButtonClick(event) {
        this.props.onChangePage(event, 0);
    };

    handleBackButtonClick(event) {
        this.props.onChangePage(event, this.props.page - 1);
    };

    handleNextButtonClick(event) {
        this.props.onChangePage(event, this.props.page + 1);
    };

    handleLastPageButtonClick(event) {
        this.props.onChangePage(
            event,
            Math.max(0, Math.ceil(this.props.count / this.props.rowsPerPage) - 1),
        );
    };

    render() {
        const {classes, count, page, rowsPerPage, theme} = this.props;

        return (
            <div className={classes.root}>
                <IconButton
                    onClick={this.handleFirstPageButtonClick}
                    disabled={page === 0}
                    aria-label="First Page"
                >
                    <FirstPageIcon/>
                </IconButton>
                <IconButton
                    onClick={this.handleBackButtonClick}
                    disabled={page === 0}
                    aria-label="Previous Page"
                >
                    <KeyboardArrowLeft/>
                </IconButton>
                <IconButton
                    onClick={this.handleNextButtonClick}
                    disabled={page >= Math.ceil(count / rowsPerPage) - 1}
                    aria-label="Next Page"
                >
                    <KeyboardArrowRight/>
                </IconButton>
                <IconButton
                    onClick={this.handleLastPageButtonClick}
                    disabled={page >= Math.ceil(count / rowsPerPage) - 1}
                    aria-label="Last Page"
                >
                    <LastPageIcon/>
                </IconButton>
            </div>
        );
    }
}

const TablePaginationActionsWrapped = withStyles(actionsStyles, {withTheme: true})(
    TablePaginationActions,
);

class CategoryPanel extends React.Component {
    constructor(props) {
        super(props);
        const {onPresentSnackbar, match} = this.props;
        console.log(match.params.token);

        this.state = {
            onPresentSnackbar: onPresentSnackbar,
            categories: [],
            loaded: true,
            perPage: 10,
            page: 1,
            query: '*',
            open: false,
            categoryId: null,
        }
        ;

        this.getAllCategory = this.getAllCategory.bind(this);
        this.handleChangeRowsPerPage = this.handleChangeRowsPerPage.bind(this);
        this.handleChangePage = this.handleChangePage.bind(this);
        this.handleChangeQuery = this.handleChangeQuery.bind(this);
        this.handleClose = this.handleClose.bind(this);
    }

    componentDidMount() {
        this.getAllCategory()
    }

    getAllCategory() {
        this.setState({
            loaded: true,
        });
        axios.get(`/api/category?page=${this.state.page}&limit=${this.state.perPage}&query=${this.state.query}`).then((response) => {
            this.setState({
                categories: response.data,
                loaded: false,

            });
            console.log(response.data)
        });
    }

    handleChangePage(e, page) {
        this.setState({
            page: page + 1
        }, () => {
            this.getAllCategory()
        })
    }

    handleChangeRowsPerPage(e) {
        this.setState({
            perPage: e.target.value
        }, () => {
            this.getAllCategory();
        });
    }
    handleChangeQuery(e) {
        this.setState({
            query: e
        }, () => {
            this.getAllCategory();
        });
    }
    handleClose() {
        this.setState({
            open: false,
        })
    }

    render() {
        const classes = this.props.classes;
        let categoryView;
        if (this.state.loaded === true) {
            categoryView = <CircularProgress className={classes.progress} color="secondary"/>
        } else {
            categoryView = <Table className={classes.table}>
                <TableHead>
                    <TableRow>
                        <TableCell>Id Category</TableCell>
                        <TableCell>Name</TableCell>
                        <TableCell></TableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {this.state.categories['data'].map(category => {
                        return (
                            <TableRow key={category.id}>
                                <TableCell component="th" scope="row">
                                    {category.id}
                                </TableCell>
                                <TableCell scope="row">
                                    <NavLink to={"/panel/edytuj/kategorie/" + category.id}>
                                        {category.name}
                                    </NavLink>
                                </TableCell>
                                <TableCell scope="row">
                                    <Button variant="contained" color="secondary" onClick={() => {
                                        this.setState({
                                            open: true,
                                            categoryId: category.id
                                        })
                                    }}>
                                        <DeleteForeverOutlinedIcon/>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        );
                    })}
                    <Modal
                        aria-labelledby="simple-modal-title"
                        aria-describedby="simple-modal-description"
                        open={this.state.open}
                        onClose={this.handleClose}
                    >
                        <div>
                            Czy napewno chcesz usunąc kategorie o id {this.state.categoryId}
                            <Button variant="contained" color="secondary"
                                    onClick={this.handleClose}>
                                Nie
                            </Button>
                            <Button variant="contained" color="secondary" onClick={() => {
                                axios.delete('/api/v1/category/' + this.state.categoryId, {
                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                }).then((response) => {
                                    this.state.onPresentSnackbar('success', 'Kategorie Usunięto');
                                    this.setState({
                                        open: false
                                    }, () => {
                                        this.getAllCategory()
                                    });
                                })
                            }}>
                                Tak
                            </Button>
                        </div>
                    </Modal>
                </TableBody>
                <TableFooter>
                    <TableRow>
                        <TablePagination
                            colSpan={10}
                            count={this.state.categories.total}
                            rowsPerPage={this.state.perPage}
                            page={this.state.categories.page - 1}
                            onChangePage={this.handleChangePage}
                            onChangeRowsPerPage={this.handleChangeRowsPerPage}
                            ActionsComponent={TablePaginationActionsWrapped}
                        />
                    </TableRow>
                </TableFooter>
            </Table>
        }
        return (
            <React.Fragment>
                <CssBaseline/>
                <main className={classes.layout}>
                    <Paper className={classes.paper}>
                        <CategoryPanelForm handleChange={this.handleChangeQuery} query={this.state.query}/>
                        {categoryView}
                    </Paper>
                    <Paper className={classes.paper}>
                        <NavLink to="/panel/kategorie/dodaj">
                            <Button variant="contained" color="primary">
                                Dodaj kategorie
                            </Button>
                        </NavLink>
                        <Button variant="contained" color="secondary">
                            Wróć
                        </Button>
                    </Paper>
                </main>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(CategoryPanel));